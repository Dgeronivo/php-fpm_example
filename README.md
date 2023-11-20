Excel project

To start, run docker-compose up.

I considered the case of dependent cells, checked the raw characters in the formula.
Use opcache to speed up php.

Next steps:
1. Write test. It`s really need
2. Need create cache for results. The principle is as follows:
After updating a cell, searches all related cells and updates their values.
If the value of a cell is deleted, its value becomes empty. Thus, it is not necessary to always enumerate values during operations.
3. For cache for results, I need DB, because don`t how do maneToMany on redis. Redis use because DB use many disk space
4. Remove repeating codes and add improve OOP concept
