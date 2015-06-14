
DROP TABLE IF EXISTS eayso_certs;

CREATE TABLE eayso_certs
(
  id INT AUTO_INCREMENT   NOT NULL,
  aysoid     VARCHAR( 10) NOT NULL,
  name_first VARCHAR(255),
  name_last  VARCHAR(255),
  email      VARCHAR(255),
  phone      VARCHAR( 20),
  sar        VARCHAR( 20),
  role       VARCHAR( 20),
  badge      VARCHAR( 20),
  cert_date  VARCHAR( 16),
  mem_year   VARCHAR(  8),
  PRIMARY KEY(id),
  UNIQUE INDEX eayso_certa__aysoid_role (aysoid,role)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
