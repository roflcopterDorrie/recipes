docker exec recipes-db-1 sh -c "mariadb-dump recipes --user=root --password=admin > /tmp/recipes-backup.sql"
docker exec recipes-db-1 sh -c "tar -cf /tmp/recipes-backup.zip -C /tmp/ recipes-backup.sql"
docker cp recipes-db-1:/tmp/recipes-backup.zip E:\Websites\recipes\backups\recipes-backup.zip
powershell -Command "Rename-Item -Path E:\Websites\recipes\backups\recipes-backup.zip -NewName E:\Websites\recipes\backups\recipes-backup-$(Get-Date -Format 'yyyy-MM-dd').zip"
docker exec recipes-server-1 sh -c "tar -cf /tmp/recipes-images-backup.tar.gz -C /var/www/html/web/assets/ images"
docker cp recipes-server-1:/tmp/recipes-images-backup.tar.gz E:\Websites\recipes\backups\recipes-image-backup.tar.gz
powershell -Command "Rename-Item -Path E:\Websites\recipes\backups\recipes-image-backup.tar.gz -NewName E:\Websites\recipes\backups\recipes-image-backup-$(Get-Date -Format 'yyyy-MM-dd').tar.gz"
