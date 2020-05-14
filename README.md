# API Platform

## Installation
- clone this repo in your `api-platform` docker folder
- replace the `api` folder by this folder
- create jwt keys with this command :
```bash
docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')}
    echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

## Use fixtures
You can update files in api/fixtures and name them by your entityName.yaml
To generate fixtures :
`docker-compose exec php bin/console hautelook:fixtures:load`


Heroku : https://recrutax.herokuapp.com/ (updated from master branch)
