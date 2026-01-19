-- SQL migration to add address_id to invoices table
-- Run this to enable delivery address tracking on invoices

ALTER TABLE `invoices` 
ADD COLUMN `address_id` int DEFAULT NULL AFTER `client_id`,
ADD KEY `address_id` (`address_id`),
ADD CONSTRAINT `invoices_address_fk` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL;
