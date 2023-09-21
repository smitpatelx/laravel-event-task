read -p "⚡️ (Docker for db only. Starting...) Type: <u | ud | p | d | r> " command

env_file=".env"
docker_compose_file="docker-compose-db.yaml"

if [[ $command == 'u' ]]
then
    docker-compose --env-file $env_file -f $docker_compose_file up
elif [[ $command == 'ud' ]]
then
    docker-compose --env-file $env_file -f $docker_compose_file up -d
elif [[ $command == 'p' ]]
then
    docker-compose -f $docker_compose_file ps -a
elif [[ $command == 'd' ]]
then
    docker-compose -f $docker_compose_file down
elif [[ $command == 'r' ]]
then
    docker-compose -f $docker_compose_file down && \
    docker-compose --env-file $env_file -f $docker_compose_file up -d
fi
