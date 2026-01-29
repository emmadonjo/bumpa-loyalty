# BUMPA LOYALTY APP

Bumpa Loyalty app is a gamification solution to reward customers with great patronage.

## Applications
- Frontend: A NextJs frontend app serving both the admin and customer portals
- Backend: A laravel Domain-Driven Development API structured to ensure scalability. Follows the repository pattern as well.

## Local Setup
### 1. Requirements
- Docker & Docker Compose

### 2. Installation
NB: The docker setup/install script is intended to make the installation a breeze.
Before installing, ensure you can execute basic `sudo` command. You will only need one `chmod +x install.sh` if you intend to use the installation I've provided.

- If you are on windows, use `wsl` or `git batch`
- copy `.env.example` in the root folder to `.env`. If you make changes to the DB values, update the corresponding ones in the `db` container of `docker-compose.yml`
- Ensure Docker & docker compose are installed and active
- Run `chmod +x install.sh` to give permission to the bash scripts located at `./install.sh`. This script will set up the container, install dependencies and services for both apps and finally run the apps.
- Now, run the installation: while on the projects root folder on a terminal, enter `./install.sh`.
- Once the setup and installation are complete, you should the URLs on the terminal. The frontend will be available at: [http://localhost:3000](http://localhost:3000)

**NB**
The installation script - `install.sh` is intended for development environment. in production, docker and docker-composer commands can be used.

If you're comfortable with docker commands, you can skip the installation script and run commands to build, detach volume, etc. E.g. `docker compose up -d`


## Accessing the Portals
Default user accounts have been created automatically, assuming the setup went well. Use the details below to access any of the portals of your choosing:

**Admin Portal**
- email: **admin@example.com**
- password: **password**

**Customer Portal**
- email: **customer@example.com**
- password: **password**


## Purchase Simulation
The customer portal comes with a purchase simulation that allows you to make purchases geared towards increasing your rewards: badges, achievements and loyalties.