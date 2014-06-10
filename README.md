Rackpower
=========

Rackpower is a web-based tool to help sysadmins diagram racks, manage UPS loads, and calculate expected uptimes.

Requirements
--
- php >= 5.3 (may work with older versions, not tested)
- mysqli module for php
- mysql server
- an httpd

Setup
--
- Set up the database by importing the provided structure file
- Copy the source code to somewhere your httpd can serve it
- Modify config.inc.php to specify your mysql config and desired access password
- If you are using HTTP auth, you can disable the app's internal auth (which is not very robust)
- If you have an instance of GLPI that you would like to gather additional data from, specify its MySQL information as well
- Nagivate your browser to the URL where you installed Rackpower, and log in
- Use the 'Manage Racks' option to add the desired number of racks
- Use the 'Manage Groups' option to add desired groups
- Use the 'New Entity' option to add entities to the racks. Add UPSs before servers.

Entities fields
--
- *Hardware*: Usually used for the name of an entity.
- *Type*: Specifies whether an entity will provide power, consume power, or neither. For example, a server is a Consumer while a UPS is a Provider. A sliding shelf is neither, so it has the type Other.
- *Group*: A classification for the entity which specifies its display color. Simply a visual aid, does not affect calculations.
- *Position*: Specifies where the entity is installed (rack and unit). Cannot overlap with another entity.
- *Height*: Specifies the height of the entity, in standard U's
- *Consumer Parameters*: This section is required when configuring a device which consumes power.
  - *Total Consumption*: The total power consumption of the machine (watts)
  - *Power Supply 1-4*: Select which UPSs, if any, provides power to the machine. Select the checkbox to enable the supply. If a supply is not enabled, it will be displayed with -strikethrough- and not used to calculate loads or uptimes.
  - *GLPI ID*: The unique ID for the Computer object in GLPI. Rackpower will display selected information from GLPI if this is set.
- *Provider Parameters*: This section is required when configuring a device which provides power.
  - *Capacity*: The maximum capacity of the UPS, in watts
  - *Runtime*: A formula to calcualte the runtime of the UPS. Since most runtimes have exponential runtime curves, we use `R = A * e^(B * W)`, where R is runtime in minutes and W is the load in watts. The constants A and B will vary with each UPS configuration and are entered here. Use an exponential regression calculator in combination with data from the UPS manual to fill in these fields.
- *Comments*: Any comments about the device can go here. The comments are displayed as a tooltip when hovering over the entity in the rack.
