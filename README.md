# NoSpend - Power Up Your Wallet

## Description

NoSpend is an expense tracker designed to help you manage your finances efficiently. The main features of the first MVP include the ability to create recurring expenses with automatic payments, as well as the option to manually create and mark expenses as paid when necessary.

## Installation

### Backend (API)

1. Navigate to the `api/` directory.
2. Run `composer install` to install dependencies.
3. Configure your environment variables in the `.env` file.
4. Start the development container using Laravel Sail:
   ```sh
   sail up -d # or vendor/bin/sail up -d
   ```

### Frontend (App)

1. Navigate to the `app/` directory.
2. Run `npm install` to install dependencies.
3. Configure the API URL in the `.env.local` file.
4. Start the development server:
   ```sh
   npm run dev
   ```

## Usage

NoSpend is an intuitive web application. Follow these steps to get started:

1. **Sign Up**: Create an account if you don't have one.
2. **Log In**: Sign in with your credentials.
3. **Create Expenses**: Use the "New Expense" button to add a new expense.
4. **View Expenses**: Click on "All Expenses" to see a monthly overview of your expenses.

## Technologies Used

| For...          | I used...                                                                                                                                                                                                            |
| --------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Backend**     | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)                                                                                                             |
| **Database**    | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)                                                                                                                   |
| **Frontend**    | ![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D) ![Vuetify](https://img.shields.io/badge/Vuetify-1867C0?style=for-the-badge&logo=vuetify&)                     |
| **Hosting**     | ![Hetzner](https://img.shields.io/badge/Hetzner-D50C2D?style=for-the-badge&logo=hetzner&logoColor=white)                                                                                                             |
| **Proxy & DNS** | ![Nginx](https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white) ![Cloudflare](https://img.shields.io/badge/Cloudflare-F38020?style=for-the-badge&logo=cloudflare&logoColor=white) |

## Contact

For any questions or discussions about the project, you can reach me at:

- **Email**: [matheus@dresch.dev](mail:matheus@dresch.dev)
- **LinkedIn**: [Matheus Dresch](https://www.linkedin.com/in/matheus-dresch/)
