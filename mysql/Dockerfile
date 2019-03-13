FROM mysql:5.6

ENTRYPOINT [ \
    "docker-entrypoint.sh", \
    "--default-authentication-plugin=mysql_native_password", \
    "--character-set-server=utf8", \
    "--collation-server=utf8_general_ci" ]
