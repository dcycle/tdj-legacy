(1) Log into http://terredesjeunes.org as an admin
(2) Go to http://terredesjeunes.org/admin/content/backup_migrate
(3) Compression: Gzip, backup database
(4) Make sure uncompressed version exists in /Users/albert/Documents/dev/docker/tdj/tdj-mirror/legacy-database/database.sql (it takes about 100 Mb)
(5) cd ./legacy-files/
(6) rm -rf files
(7) sftp lydie@aegir.koumbit.org
(8) get -r terredesjeunes.org/files
