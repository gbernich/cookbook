filename="restore_$(date '+%Y_%m_%d').sql"

sudo mysqldump Cookbook > ~/cookbook/backups/$filename

sudo cp ~/cookbook/backups/$filename /var/www/html/backups/$filename
