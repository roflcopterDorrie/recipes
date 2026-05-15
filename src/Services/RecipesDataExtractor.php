<?php

namespace Drupal\recipes\Services;

use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ai\OperationType\Chat\ChatInput;
use Drupal\ai\OperationType\Chat\ChatMessage;
use Drupal\ai\AiProviderPluginManager;
use Drupal\ai\Plugin\ProviderProxy;
use Drupal\Core\Http\ClientFactory;
use DOMDocument;
use Drupal\Core\Messenger\MessengerInterface;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Drupal\ai\Dto\StructuredOutputSchema;

class RecipesDataExtractor
{
  use DependencySerializationTrait;

  protected ImmutableConfig $config;
  protected ProviderProxy $ai_proxy_provider;

  public function __construct(
    protected RecipesDataValidator $recipes_data_validator,
    protected ConfigFactoryInterface $config_factory,
    protected AiProviderPluginManager $ai_provider,
    protected EntityTypeManagerInterface $entity_type_manager,
    protected ClientFactory $httpClient,
    protected MessengerInterface $messenger
  ) {
    $this->config = $this->config_factory->get('recipes.settings');
  }

  protected function chat(ChatInput $messages)
  {
    // Setup the AI model provider so we can use it.
    $ai_provider_settings = $this->ai_provider->getDefaultProviderForOperationType('chat');
    $ai_proxy_provider = $this->ai_provider->createInstance($ai_provider_settings['provider_id']);
    $ai_model_id = $ai_provider_settings['model_id'];
    return $ai_proxy_provider->chat($messages, $ai_model_id);
  }

  public function generatePrompt(string $url) : string
  {
    $ingredientAisles = [];
    $ingredientAisleTerms = $this->entity_type_manager->getStorage('taxonomy_term')->loadByProperties(['vid' => 'recipes_ingredient_aisle']);
    foreach ($ingredientAisleTerms as $ingredientAisle) {
      $ingredientAisles[] = '- ' . $ingredientAisle->getName() . ': ' . PHP_EOL;
    }

    $client = $this->httpClient->fromOptions();
    $response = $client->request('GET', $url, [
      'timeout' => 10,
      'headers' => [
        'User-Agent' => 'Drupal Recipe Scraper/1.0',
      ],
    ]);

    $html = $response->getBody()->getContents();

    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $removeTags = ['script', 'style', 'noscript'];

    foreach ($removeTags as $tagName) {
      $nodes = $dom->getElementsByTagName($tagName);
      while ($nodes->length > 0) {
        $node = $nodes->item(0);
        $node->parentNode->removeChild($node);
      }
    }

    $body = $dom->getElementsByTagName('body')->item(0);
    $recipe_website_text = $body->nodeValue;

    $prompt = $this->config->get('prompt');

    $sanitisedPrompt = t($prompt, [
      '@website_text' => $recipe_website_text,
      '@ingredient_aisle_taxonomy' => implode(" ", $ingredientAisles),
      '@schema' => $this->recipes_data_validator->getSchema()
    ]);

    return $sanitisedPrompt->__toString();
  }

  public function extractRecipeFromUrl(string $url): bool|object
  {
    $prompt = $this->generatePrompt($url);

    $input = new ChatInput([new ChatMessage('user', $prompt)]);

    if ($this->config->get('use_structured_json_ai_response')) {
      //Force the AI to use a structured output. This is having issues on the Gemini provider so it is commented out.
      $schema_text = $this->recipes_data_validator->getSchema();
      $schema = new StructuredOutputSchema(
        name: 'json',
        description: 'Structured json data',
        strict: TRUE,
        json_schema: json_decode($schema_text, TRUE),
      );
      $input->setChatStructuredJsonSchema($schema);
    }

    $response = $this->chat($input);
    $return_message = $response->getNormalized();

    $recipe_text = $return_message->getText();

    //$recipe_text = '{"title":"Vegan Shepherd\u2019s Pie with Gravy","ingredients":[{"amount":"3 lb","name":"potatoes","extra":"peeled and chopped","category":"Fresh Fruits and Vegetables"},{"amount":"2 tbsp","name":"Earth Balance","extra":"or equivalent","category":"Baking Ingredients"},{"amount":"1\/3 cup + 2 tbsp","name":"non-dairy milk","extra":"I used soy","category":"Plant based Milk"},{"amount":"1 tsp","name":"kosher salt","extra":"or to taste","category":"Spices and Seasoning"},{"amount":null,"name":"black pepper","extra":"freshly ground, to taste","category":"Spices and Seasoning"},{"amount":"1\/2 tsp","name":"garlic powder","category":"Spices and Seasoning"},{"amount":"2 tbsp","name":"extra virgin olive oil","category":"Oils"},{"amount":"1","name":"yellow onion","extra":"finely chopped","category":"Fresh Fruits and Vegetables"},{"amount":"3 cloves","name":"garlic","extra":"minced","category":"Fresh Fruits and Vegetables"},{"amount":"4 medium","name":"carrots","extra":"peeled & small dice","category":"Fresh Fruits and Vegetables"},{"amount":"2","name":"parsnips","extra":"peeled & small dice","category":"Fresh Fruits and Vegetables"},{"amount":"4 stalks","name":"celery","extra":"small dice","category":"Fresh Fruits and Vegetables"},{"amount":"1 cup","name":"vegetable broth","extra":"full sodium","category":"Stock"},{"amount":"1\/4 cup","name":"red wine","category":"Condiments"},{"amount":"2 tsp","name":"dried thyme","category":"Herbs"},{"amount":"1\/2 tsp","name":"Italian seasoning","category":"Spices and Seasoning"},{"amount":"1\/2-3\/4 tsp","name":"kosher salt","extra":"to taste","category":"Spices and Seasoning"},{"amount":"3 tbsp","name":"flour","extra":"I used whole wheat","category":"Baking Ingredients"}],"steps":["Preheat oven to 425\u00b0F and lightly oil a 2.5 quart\/2.3 litre casserole dish.","Place peeled and chopped potatoes into a large pot and add water, 2 inches above potatoes. Bring to a boil and then simmer on low for about 30 minutes until very tender.","Meanwhile, prepare the vegetable filling. Chop the onion and mince the garlic and add to a skillet along with the oil. Cook on low for about 5-7 minutes. Now add in the chopped carrots, parsnip, and celery. Cook on medium-low heat for about 15 minutes.","When the potatoes are done cooking, drain and add back to the pot. Add the Earth Balance (or butter), milk, and seasonings and mash well. Set aside.","In a small bowl, whisk together the liquid ingredients (broth, red wine (optional), thyme, and flour). Add this liquid mixture to the vegetables in the skillet and stir well. Add your salt and pepper to taste. Cook for another 5-10 minutes or so until thickened. Season to taste.","Scoop vegetable mixture into casserole dish. Spread on the mashed potato mixture and garnish with paprika, ground pepper, and Thyme. Bake at 425\u00b0F for about 35 minutes, or until golden and bubbly.","Allow to cool for at least 10 minutes before serving."]}';

    if (($extracted_recipe = json_decode($recipe_text)) !== NULL) {
      $result = $this->recipes_data_validator->validate($extracted_recipe, $this->recipes_data_validator->getSchema());

      if ($result->isValid()) {
        return $extracted_recipe;
      } else {
        $errors = new ErrorFormatter()->format($result->error());
        foreach($errors as $error) {
          $this->messenger->addError(t('Validation error: @msg', ['@msg' => $error[0]]));
        }
        $this->messenger->addError($recipe_text);
        
      }
    } else {
      $this->messenger->addError(t('String returned from AI could not be json decoded: @ai_string', ['@ai_string' => $recipe_text]));
      $this->messenger->addError(t('Prompt: @prompt', ['@prompt' => $prompt]));
    }

    
    return FALSE;
  }
}
