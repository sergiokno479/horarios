# Generador de Horarios Académicos

Algoritmo de optimización desarrollado en **Symfony 7** que automatiza la creación de horarios universitarios, resolviendo conflictos de cruce de materias basado en preferencias del usuario.

##  Características
- **Motor de Optimización:** Algoritmo personalizado para filtrar combinaciones válidas.
- **Backend Robusto:** Arquitectura MVC implementada con Symfony.
- **Gestión de Datos:** Uso de Doctrine ORM para modelado de entidades complejas.

##  Tecnologías
- PHP 8.2
- Symfony 7
- MySQL
- Twig

##  Instalación (Local)
1. Clonar repositorio:
   ```bash
   git clone [https://github.com/sergiokno479/horarios.git](https://github.com/sergiokno479/horarios.git)

2. Instalar dependencias:
   ```bash
      composer install

3. Configurar base de datos en .env y ejecutar migraciones:
      ```bash
      php bin/console doctrine:migrations:migrate
4. Correr servidor:
      ```bash
      symfony server:start
