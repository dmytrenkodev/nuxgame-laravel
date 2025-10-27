# NuxGame Laravel Test Task

## Running

1. Clone repository

 - ``` git clone https://github.com/dmytrenkodev/nuxgame-laravel-test.git ```

2. Go to 
 - ``` cd nuxgame-laravel-test ```

3. From root directory
 - ``` docker compose up -d --build ```

4. Install dependencies
 - ``` docker exec -it laravel_app /bin/bash ```
 - ``` composer install ```
 - ``` cp .env.example .env ```
 - ``` php artisan key:generate ```
 - ``` php artisan migrate ```

6. Go to
 - ``` http://localhost:8888 ```

## Possible Improvements

- Add proper frontend styling using CSS or a framework (Bootstrap, Tailwind)
- Implement client-side validation for the registration form
- Add pagination or filtering for the lucky history
- Implement user authentication instead of relying solely on tokens
- Add unit and integration tests for controllers and database interactions
- Use environment variables for database credentials instead of hardcoding
- Implement rate limiting to prevent abuse of the "I'm Feeling Lucky" button
- Add logging for actions like link regeneration, deactivation, and lucky draws
- Support multiple languages / localization
