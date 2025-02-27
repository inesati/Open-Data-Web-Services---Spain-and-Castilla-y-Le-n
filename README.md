# Open Data Web Services - Spain and Castilla y León

This project implements the consumption of data from different open web services provided by the Spanish Government and the Castilla y León Government. The following data is retrieved and displayed:

1. **Economic Sector Occupation Data** (Industry, Services, etc.) using the EPA (Active Population Survey).
2. **Castilla y León Data** about:
   - Electric vehicle charging points.
   - Public employment call for applications between two given dates.
   - Pharmacies in Castilla y León.
   - COVID-19 mortality rate by health zones.

The results of these queries are visualized on a responsive webpage.

## Description

The project is divided into several parts:

1. **EPA Data Consumption**: Retrieval of the percentage of occupied persons by economic sector, filtered by year and province (specifically for Castilla y León).
2. **Castilla y León Services**:
   - Electric vehicle charging points.
   - Public employment call for applications within a given date range.
   - Pharmacies.
   - COVID-19 mortality rate by health zones.

These services allow users to query information interactively.

## Features

- Data consumption from the following sources:
  - **Spanish Government (EPA)**: Data on sector occupation.
    - [EPA API](https://servicios.ine.es/wstempus/js/es/DATOS_TABLA/3995?tip=AM)
  - **Castilla y León Government**:
    - [Electric vehicle charging points](https://analisis.datosabiertos.jcyl.es/)
    - [Public employment calls](https://analisis.datosabiertos.jcyl.es/)
    - [Pharmacies](https://analisis.datosabiertos.jcyl.es/)
    - [COVID-19 mortality rate by health zones](https://analisis.datosabiertos.jcyl.es/)
  
- Visualization of the retrieved results through a responsive web frontend.
- **Filtering** by province and year for querying sector occupation data.

## Requirements

- **PHP 7.0 or higher** (for backend, if PHP is used for requests).
- **HTML5, CSS3, and JavaScript** (for the responsive frontend).
- **Castilla y León Government API** (for accessing Castilla y León data).
- **EPA API** from the Spanish Government.

## Installation

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/your-username/open-data-web-services.git
Navigate to the repository folder:

bash
Copiar
Editar
cd open-data-web-services
Make sure you have a running web server (Apache, Nginx, etc.), or simply use an embedded PHP server if you don't have one set up:

bash
Copiar
Editar
php -S localhost:8000
Access the application from your browser at http://localhost:8000.

Usage
Sector Occupation Data:
Enter the year and province to retrieve the economic sector occupation data (Industry, Services, etc.).
Castilla y León Data:
View electric vehicle charging points.
Retrieve public employment calls for the selected date range.
View pharmacies.
Access the COVID-19 mortality rate by health zones.
All results are displayed visually on a responsive webpage, ensuring they are accessible on both mobile and desktop devices.

Contributions
Contributions are welcome. If you have suggestions, improvements, or fixes, please open a pull request.

License
This project is licensed under the MIT License - see the LICENSE file for details.
