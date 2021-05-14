# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1f3ee1424707446ba0f453ca1afb08ce)](https://app.codacy.com/gh/florianleboul/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=florianleboul/SnowTricks&utm_campaign=Badge_Grade_Settings)

## Requirements

The above application require following environment :
- php >= 7.0
- mysql >= 5.6
- symfony >= 5.2.5
- composer >= 2.0.8

## Installation

In this installation guide, it's supposed that you have your environment configured (see requirements)
1. Download zip and extract it on your server or clone repository from github :
```
git clone https://github.com/Xwyk/SnowTricks.git
```
2. Create your .env.local file from .env present in project's root

3. Install dependancies
```
composer install
```

4. (Optionnal) Init project by injecting default datas (can be used to reset project for testing purpose)
This create 4 users, "user1" to "user4", with password "user1" to "user4"
```
composer reset
```