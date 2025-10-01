CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS trips (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  title TEXT NOT NULL,
  description TEXT,
  latitude DOUBLE PRECISION,
  longitude DOUBLE PRECISION,
  location_name TEXT,
  cost NUMERIC(12,2),
  heritage_sites TEXT,
  places_to_visit TEXT,
  rating_convenience INTEGER CHECK (rating_convenience BETWEEN 1 AND 5),
  rating_safety INTEGER CHECK (rating_safety BETWEEN 1 AND 5),
  rating_population INTEGER CHECK (rating_population BETWEEN 1 AND 5),
  rating_vegetation INTEGER CHECK (rating_vegetation BETWEEN 1 AND 5),
  is_public BOOLEAN NOT NULL DEFAULT TRUE,
  created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_trips_user ON trips(user_id);
CREATE INDEX IF NOT EXISTS idx_trips_public ON trips(is_public);

CREATE TABLE IF NOT EXISTS trip_photos (
  id SERIAL PRIMARY KEY,
  trip_id INTEGER NOT NULL REFERENCES trips(id) ON DELETE CASCADE,
  path TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_photos_trip ON trip_photos(trip_id);
