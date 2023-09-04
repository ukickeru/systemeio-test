This folder contains basic Docker setup for dev purposes.

Sample commands to control Docker dev build:

* **Build**: ```docker compose -f docker-compose.yml -f ./dev/docker-compose.override.yml --env-file=./dev/.env up -d --build```

* **Up**: ```docker compose -f docker-compose.yml -f ./dev/docker-compose.override.yml --env-file=./dev/.env up```

* **Stop**: ```docker compose -f docker-compose.yml -f ./dev/docker-compose.override.yml --env-file=./dev/.env stop```

* **Down**: ```docker compose -f docker-compose.yml -f ./dev/docker-compose.override.yml --env-file=./dev/.env down```

This commands overrides original ```docker-compose.yml``` file and also substitute specific ```.env``` file.

You may modify original files or make and use different.
