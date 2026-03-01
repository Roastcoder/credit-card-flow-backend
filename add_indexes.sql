-- Add indexes to credit_cards table for faster queries
CREATE INDEX IF NOT EXISTS idx_credit_cards_status ON credit_cards(status);
CREATE INDEX IF NOT EXISTS idx_credit_cards_bank ON credit_cards(bank);
CREATE INDEX IF NOT EXISTS idx_credit_cards_created_at ON credit_cards(created_at DESC);
