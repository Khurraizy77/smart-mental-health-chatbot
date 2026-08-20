# Rubric Implementation Checklist

## Application Completeness

The system includes student registration, login, dashboard, chatbot, chat history, mood tracking, recommendations, counselling referral requests, profile update, user PDF report, admin dashboard, student management, referral management, and admin PDF report.

## GUI

The system uses responsive Bootstrap pages with role-based navigation, dashboard cards, charts, chat-style conversation layout, counselling cards, profile forms, admin tables, PDF buttons, hospital map, and motivational slideshow.

## Complexity Programming

Database programming artifacts are implemented in `2026_06_19_000001_add_database_programming_artifacts.php`.

- Trigger `trg_mood_tracking_after_insert` records mood activity into `system_activity_logs`.
- Trigger `trg_referrals_after_insert` records referral activity into `system_activity_logs`.
- Stored procedure `sp_user_mood_summary` returns grouped user mood analysis.
- Stored procedure `sp_admin_system_summary` returns admin-level system totals.

## Complexity Data Query

The admin dashboard uses grouped aggregate and joined queries:

- Mood count grouped by mood type.
- Referral count grouped by status.
- Faculty mood risk summary using `users`, `students`, and `mood_tracking`.
- Recent emergency mood list using joined student and mood data.

## Error Handling

The system includes validation and user-facing error messages for registration, login, profile update, chat message submission, counselling referral submission, password update, and account deletion. Chat and referral saving use database transactions and return informative messages if saving fails.

## Database Integration

The system integrates Laravel models, migrations, relationships, transactions, triggers, stored procedures, foreign keys, cascade/null-on-delete behavior, dashboard queries, PDF report queries, and automated tests.

## Verification

Current automated test result:

```bash
php artisan test
```

Result: `33 passed`.
