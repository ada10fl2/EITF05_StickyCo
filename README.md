#EITF05 - Web Security - Project
A webshop was implemented, using PHP and MySql

## Assignment
http://www.eit.lth.se/fileadmin/eit/courses/eitf05/project/webshop.pdf

## Configuration 

### Configure PHP
Run `nano /etc/php.ini`
```	
# Set session store to be outside of the www-root
session.save_path="/var/lib/php/session"
	
# Set the file upload to be outside of the www-root
upload_tmp_dir="/var/lib/php/session"
```	

###Configure Apache
Run `nano /etc/php.d/secutity.ini`
```
# Dont leak information
expose_php=Off
display_errors=Off
file_uploads=Off
log_errors=On
error_log=/var/log/httpd/php_scripts_error.log
	
# Disable remote code fetching
allow_url_fopen=Off
allow_url_include=Off
	
	
# Ineffective and not very robust
# mysql_escape_string() and custom filtering functions serve a better purpose
magic_quotes_gpc=Off
	
# Attackers may attempt to send oversized POST requests to eat your system resources
post_max_size=2K
	
# set in seconds
max_execution_time =  10
max_input_time = 30
memory_limit = 40M
	
# Disable all dangerous php functions
disable_functions =exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
	
# PHP scripts are able to access files only when their owner is the owner of the PHP scripts
safe_mode = On
safe_mode_gid = Off
	
# Allow safemode to open/execute files only in specified directories
open_basedir = directory[:...]
safe_mode_exec_dir = directory[:...]
```

## Configure `www-root`, in deployed system only
```	
# Make sure path is outside /var/www/html and not readable or writeable by any other system users:
run:
	ls -Z /var/lib/php/
expect:
	drwxrwx---. root apache system_u:object_r:httpd_var_run_t:s0 session
	
# Make sure you run Apache as a non-root user such as Apache or www
chown -R apache:apache /var/www/html/
chmod -R 0444 /var/www/html/

# Ensure all directories permissions are set to 0445
find /var/www/html/ -type d -print0 | xargs -0 -I {} chmod 0445 {}
	
# Write protect configuration files
chattr +i /etc/php.ini
chattr +i /etc/php.d/*
chattr +i /etc/my.ini
chattr +i /etc/httpd/conf/httpd.conf
chattr +i /etc/
chattr +i /var/www/html/
```
