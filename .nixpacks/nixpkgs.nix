{ config, pkgs, ... }:

let
  phpVersion = "php8.2";
  extensions = [
    pkgs.phpPackages.composer
    pkgs.phpPackages.bcmath
    pkgs.phpPackages.curl
    pkgs.phpPackages.gd
    pkgs.phpPackages.mbstring
    pkgs.phpPackages.mysql
    pkgs.phpPackages.xml
  ];
in

{
  environment.systemPackages = with pkgs; [
    phpVersion
    pkgs.php
    pkgs.nodejs
    pkgs.npm
    pkgs.nginx
    pkgs.git
    pkgs.unzip
  ];

  services.nginx = {
    enable = true;
    virtualHosts = {
      "example.com" = {
        root = "/app/public";
        index = "index.php";
      };
    };
  };

  systemd.services = {
    "php-fpm" = {
      wantedBy = [ "multi-user.target" ];
      serviceConfig = {
        ExecStart = "${pkgs.php}/${phpVersion}/bin/php-fpm";
        Restart = "always";
      };
    };
  };

  NIXPACKS_BUILD_CMD = "composer install --ignore-platform-reqs && php artisan migrate --force";
}
