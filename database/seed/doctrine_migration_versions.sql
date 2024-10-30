CREATE TABLE doctrine_migration_versions
(
    version        VARCHAR(191) NOT NULL
        PRIMARY KEY,
    executed_at    DATETIME     NULL,
    execution_time INT          NULL
);

INSERT INTO `salt-lite-app`.doctrine_migration_versions (version, executed_at, execution_time)
VALUES ('PhoneBurner\\SaltLite\\Migrations\\Version20241017031636', '2024-10-18 20:24:08', 3);
