# Quizwiz

There are two users:

1. Quizmaster
2. Participants

## Quizmaster

1. Creates the [[Quiz]]
2. Creates a [[Game]] for the quiz and gets a unique code for it
3. Controls the flow of [[Questions]]
4. Ends the [[Game]]

## Participants

1. Joins the [[Game]] using the unique code + verified email
2. Answers the [[Questions]] as they appear on the screen

## Gameplay

1. Every [[Participant]] starts with [[Health]] of 100 points
2. Going down the quiz, the [[Health]] decreases.
    - One attempt per question
    - Attempting to answer a question takes away 5 points
    - Answering a question correctly gives back 3 points
    - Answering the [[Powerup]] questions correctly gives 5 points (every 10th question)
    - When [[Health]] comes down to 0, the player is eliminated
3. LIVE [[Leaderboard]] is available on /leaderboard page
4. Top 3 players play on the stage and the others play along from their seats

## Models

**Quiz**
id
name
description

**Game** (an instance of Quiz)
id
quiz_id
master_code
player_code

**Participant**
