# User Data Importer 🚀

A robust PHP CLI tool for importing CSV user data into PostgreSQL with advanced controls for transactions, batch processing, and duplicate handling.

![CLI Demo](/assets/cli-demo.gif) *CLI workflow demonstration*

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Basic Commands](#basic-commands)
  - [Advanced Scenarios](#advanced-scenarios)

## Features ✨

- **CSV Validation**  
  - Header row detection & column count checks
  - Email format validation
- **Database Operations**  
  - Table creation/rebuild
  - Transaction support (ACID compliance)
- **Data Sanitization**  
  - Name/surname capitalization
  - Email normalization (lowercase)
- **Execution Modes**  
  - Dry-run validation
  - Duplicate handling strategies

## Prerequisites 🛠️

| Component       | Requirement              | Tested Version |
|-----------------|--------------------------|----------------|
| PHP             | >= 8.3                   | 8.3            |
| PostgreSQL      | >= 13                    | 14             |
| Composer        | Stable version           | 2.3.9          |
| PHP Extensions  | pgsql                    |                |

## Installation 📦

```bash
git clone https://github.com/yusufwib/user-data-importer.git
cd user-data-importer
```

## Install project dependencies

```bash
composer install
```

## Make the script executable

```bash
chmod +x scripts/user_upload.php
```

## Configuration ⚙️

### Database Setup

1. Create dedicated database:

```bash
CREATE DATABASE user_data_importer
```

## Usage 🖥️

### Basic Commands

Note: You can replace ``composer exec user_upload.php`` with ``php scripts/user_upload.php``

**1. Help Documentation**

```bash
composer exec user_upload.php -- --help
```

**2. Create/rebuild table**

```bash
composer exec user_upload.php -- \
  --create_table \
  -u postgres \
  -p supersecret \
  -h localhost
```

**3. Dry Run Validation**

```bash
composer exec user_upload.php -- \
  --file data/users.csv \
  --dry_run
  ```

**4. Combined Table Creation + Data Import**

```bash
composer exec user_upload.php -- \
  --create_table \
  --file data/users.csv \
  -u postgres \
  -p supersecret \
  -h localhost 
 ```

### Advanced Scenarios

**1. Transactional Import with Check Unique Constraint and Configurable Batch Size**

```bash
composer exec user_upload.php -- \
  --file data/users.csv \
  -u yusufwib \
  -p yusufwib \
  -h localhost \
  --use_transactions \
  --check_duplicates \
  --batch_size 200
```

**2. Create/rebuild Table with Database Name**

```bash
composer exec user_upload.php -- \
  --create_table \
  -u postgres \
  -p supersecret \
  -h localhost
  --db_name alternate_db
```

### Future Development

1. **Unit Testing Suite**
2. **Dependency Injection**

## Help?

Open an issue or contact  [yusufw2429@gmail.com](mailto:yusufwib@example.com)  
[![Support Badge](https://img.shields.io/badge/Support-Email-blue)](mailto:yusufw2429@gmail.com)
