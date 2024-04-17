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

**Player**
id
name
email
phone?

## How to play in real-time?

A [[Game]] will have a current state. That current state will be loaded on the player's screen.

1. not_started (managed by started_at)
2. (previous answer's evaluation if any) + (current question)
3. answers submitted + evaluation awaited
4. last question's evaluation
5. ended (managed by ended_at)

Each player's progress in a game will be tracked in game_player table. It will refresh before every question.

game_id
player_id
health
rank
time_spent

Each players' answers will be tracked in player_question table.

game_id
question_id
player_id
answer
evaluation (correct/incorrect)
health_spent
time_spent

# todos

[x] method to start a quiz and set the current quiz question
[x] automatically redirect to play screen after successful auth
