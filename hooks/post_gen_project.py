#!/usr/bin/env python
"""Post-generation hook.

Runs once, right after cookiecutter generates a project. Replaces the example
WordPress secret keys/salts with freshly generated random values so every
generated project gets unique salts (the template ships shared placeholders,
which are fine as examples but must not be reused across real sites).

Cookiecutter runs this from the generated project's root directory.
"""
import re
import secrets
from pathlib import Path

# The eight WordPress secret keys/salts to randomize.
SALT_KEYS = [
    "AUTH_KEY",
    "SECURE_AUTH_KEY",
    "LOGGED_IN_KEY",
    "NONCE_KEY",
    "AUTH_SALT",
    "SECURE_AUTH_SALT",
    "LOGGED_IN_SALT",
    "NONCE_SALT",
]

# The env files that carry the salts (both are read by Docker Compose).
ENV_FILES = [
    Path("docker/config/web.example.env"),
    Path("docker/config/web-local.example.env"),
]

# Character set for salts. Deliberately excludes characters that would break
# the env files or Docker Compose: no `$` (Compose treats `$word` as variable
# interpolation), and no backtick, double quote or backslash.
SALT_CHARS = (
    "abcdefghijklmnopqrstuvwxyz"
    "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    "0123456789"
    "!@#%^&*()-_=+[]{}<>?.,:;~/|"
)


def random_salt(length: int = 64) -> str:
    return "".join(secrets.choice(SALT_CHARS) for _ in range(length))


def randomize_salts(path: Path) -> None:
    if not path.exists():
        return
    text = path.read_text()
    for key in SALT_KEYS:
        # Replace the whole `KEY="..."` line with a fresh salt.
        text = re.sub(
            rf'^{key}=".*"$',
            lambda _m, k=key: f'{k}="{random_salt()}"',
            text,
            count=1,
            flags=re.MULTILINE,
        )
    path.write_text(text)


for env_file in ENV_FILES:
    randomize_salts(env_file)
