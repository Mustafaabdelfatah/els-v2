INSERT INTO `templates` (`id`, `name`, `type`, `user_id`, `icon`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Legal advice form request-legal advice', 'form', 1, 'cs-save', NULL, '2021-08-10 05:27:23', '2021-08-10 05:34:26'),
(2, 'Litigation and request form-Litigation', 'form', 1, 'cs-file-text', NULL, '2021-08-10 05:36:09', '2021-08-10 05:49:50');

INSERT INTO `template_pages` (`id`, `title`, `template_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'advice form', 1, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(7, 'Litigation request form', 2, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50');

INSERT INTO `template_page_items` (`id`, `type`, `label`, `enabled`, `required`, `website_view`, `notes`, `comment`, `width`, `height`, `childList`, `template_page_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'text', 'Requestor name', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(2, 'text', 'Employee Id', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(3, 'textarea', 'Department', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(4, 'textarea', 'Legal advice subject', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(5, 'textarea', 'Legal advice description', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(6, 'file', 'Attached file', 1, 0, 1, NULL, NULL, 'col-4', 'auto', '[{\"file\":null,\"type\":\"image\"}]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(7, 'textarea', 'The purpose of legal advice', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(8, 'select', 'Confidential legal advice', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[{\"text\":\"Top secret\"},{\"text\":\"Secret\"},{\"text\":\"Confidential\"},{\"text\":\"Normal\"}]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(9, 'textarea', 'Notes', 1, 0, 1, NULL, NULL, 'col-4', 'auto', '[]', 2, NULL, '2021-08-10 05:34:26', '2021-08-10 05:34:26'),
(34, 'text', 'Requestor name', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(35, 'text', 'Employee Id', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(36, 'text', 'Department', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(37, 'text', 'Subject', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(38, 'textarea', 'Purpose of litigation and request', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(39, 'textarea', 'Details  of the lawsuit/litigation', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(40, 'textarea', 'Notes', 1, 0, 1, NULL, NULL, 'col-4', 'auto', '[]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50'),
(41, 'select', 'Confidential Litigation', 1, 1, 1, NULL, NULL, 'col-4', 'auto', '[{\"text\":\"Top secret\"},{\"text\":\"Secret\"},{\"text\":\"Confidential\"},{\"text\":\"Normal\"}]', 7, NULL, '2021-08-10 05:49:50', '2021-08-10 05:49:50');

