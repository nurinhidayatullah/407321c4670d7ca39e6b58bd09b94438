# Email Sending API with OAuth2, RabbitMQ, and PostgreSQL

## Overview

This project implements a robust system for sending emails using a RESTful API. The system utilizes OAuth2 for authentication, RabbitMQ for queuing, and PostgreSQL for persistent data storage.

## System Architecture

1. **REST API Server**
    * Responsible for handling incoming HTTP requests, validating OAuth2 tokens, and queuing email sending tasks.
2. **OAuth2 Authentication Server with league/oauth2-server**
    * Manages user authentication and issues access tokens for API access.
3. ### RabbitMQ Queue
    * Acts as a message broker for managing the email sending tasks in an asynchronous and scalable manner.
4. ### PostgreSQL Database
    * Stores email sending history.
5. ### Email Sending Worker
    * A separate service responsible for processing queued email tasks, interacting with the SMTP server, and updating the database with sending status.

## System Flow

1. **User requests to send an email through the REST API.**
2. **API Server** validates the OAuth2 token with the **Authentication Server**
3. Upon successful validation, the API Server enqueues the email sending task into **RabbitMQ**.
4. Email Sending Worker retrieves tasks from the queue, sends emails via the SMTP server, and updates the PostgreSQL Database.

## Setting Up and Running the Project
1. Clone the repository:
    ```bash
    git clone https://github.com/your-repo/email-api.git
2. run docker-compose on the project root folder:
    ```bash 
    docker compose up -d
3. call api for login:
    ```bash 
    http://localhost/authorize
3. call api for send email:
    ```bash 
    http://localhost/send

## API Endpoints

### LOGIN
* **Endpoint: POST** /authorize
* **Request Body:**

```json
    {
        "grant_type": "client_credentials",
        "client_id": "myawesomeapp",
        "client_secret": "abc123",
        "scope": "basic email"
    }
```
* **Response:**
```json
    {
        "token_type":"Bearer",
        "expires_in":3600,
        "access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJteWF3ZXNvbWVhcHAiLCJqdGkiOiI1YzNkMWVlZjczZGFlOTFlZjY5YTcwMGI2YmM1ZWE3ZTM2NTI4ZmJlMDAwN2RlMWY3ZjhkN2FiNTkyYzZkMWEwNGI2MjNlYmNkNTVmZmFkYSIsImlhdCI6MTY5ODM0MjEwMS4wODUyODUsIm5iZiI6MTY5ODM0MjEwMS4wODUyODksImV4cCI6MTY5ODM0NTcwMC42MDQwODQsInN1YiI6IiIsInNjb3BlcyI6WyJiYXNpYyIsImVtYWlsIl19.Qb0Zf_6C1SJ-Na-xTo9BhwGNARRh2eDjUsONePPRZQFTaZAjbsB-gY6Y96NDEUx5Q9RUoXzZsle4ieXqxCYHwJ7n6S0n9K2ctGT9k4i_rxxaQXfFf11Xe-8uWVboLzzCEOc5M25u1tLFygNgSxoXAiv8SrgvwCBrjnm6H6hVDLyqoPKtkDgWOu1KC1spGm3f3DiA4wz9emMMMXR0atI9_Uag4zjZshGXOOBF_87ydeEbJsEIOxVFqYKD2FI8tNpmCdFqDGYwskb01AocEXp0sHfV9pGIt6Z07h4DL4a6TUDH1_TxxK01f_TTI0K6ulFbt3hWAE40u2BQYuWkxe7f-A"
    }
```
### SEND EMAIL
* **Endpoint: POST** /send
* **Request Body:**

```json
    {
        "email": "n.hidayatullah94@gmail.com",
        "name": "Nurin",
        "to_name": "Nurin",
        "to_email": "nrn.h2h@gmail.com",
        "subject": "Hallo Levart"
    }
```
* **Response:**
```json
    Start Queue Email
```

### CHECK EMAIL SENT

* Login to **https://mailtrap.io/**
* username: **toyor50519@undewp.com**
* password: **7NJ%7V&KTMnQ*BC**