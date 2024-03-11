# MicroServices Set up - (Users and Notifications)

## Introduction
This is a simple microservices setup for users and notifications. The users service is responsible for creating users, while the notifications service is responsible for sending storing to users. The notifications service is also responsible for consuming messages from the message broker (Redis) and storing in the log file.

### Technologies
1. **PHP (Symfony 7)** 
2. **MySQL** (For database)
3. **Redis** (For message broker)

### Pre-requisite

1. **Docker**: For containerization
2. **Composer**: For packages installation
3. **Symfony CLI**: For running symfony commands
4. **Postman**: For API testing

### Installation & Test Steps
- Clone the repository
- Open the terminal and navigate to the root directory of the project
- Once you're in the root directory, run `docker-compose up -d --build`
- Once the containers are up and running, run `docker exec -it php sh -c "cd /var/www/nb/userService && composer install"`
- Once the composer installation is done, run `docker exec -it php sh -c "cd /var/www/nb/notificationService && composer install"`
- Once the composer installation is done, run `docker exec -it php sh -c "cd /var/www/nb/userService && bin/console doctrine:migrations:migrate"`
- Once the migrations are done, run `docker exec -t php sh -c "cd /var/www/nb/notificationService && bin/console messenger:consume redis"`
- Once the consumer is running, you can now test the APIs using Postman 
- Send a POST request to `http://localhost/users` with the following payload
    ```json
    {
        "firstName": "John Doe",
        "lastName": "John Doe",
        "email": "johndoe@example.com"
  }
    ```

### Unit, Integration and Functional Tests
- To run Tests run the following commands
    - For the User service tests run `docker exec -t php sh -c "cd /var/www/nb/users-service && ./vendor/bin/phpunit"`
    - For the Notification service tests run `docker exec -t php sh -c "cd /var/www/nb/notifications-service && ./vendor/bin/phpunit"`

### Project Requirements and Details
- [x] Total TestCases : **18**
- [x] Implemented DDD (Domain Driven Design) and CQRS (Command Query Responsibility Segregation) patterns
- [x] Implemented SOLID principles
- [x] Project is fully dockerized
- [x] Works 100% as requested  ✅✅
