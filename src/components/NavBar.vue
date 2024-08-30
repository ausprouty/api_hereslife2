<template>
    <nav class="navbar">
      <div class="logo">
        <img src="@/assets/logo.png" alt="Logo">
      </div>
      <ul class="menu">
        <li class="menu-item" v-for="(category, index) in categories" :key="index">
          <a href="#">{{ category.name }}</a>
          <ul class="dropdown">
            <li v-for="(item, i) in category.items" :key="i">
              <a href="#">{{ item }}</a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    data() {
      return {
        categories: [
          {
            name: 'Emails',
            items: ['Email 1', 'Email 2', 'Email 3', 'Email 4', 'Email 5']
          },
          {
            name: 'Materials',
            items: ['Material 1', 'Material 2', 'Material 3', 'Material 4', 'Material 5']
          },
          {
            name: 'People',
            items: ['Person 1', 'Person 2', 'Person 3', 'Person 4', 'Person 5']
          },
          {
            name: 'Reports',
            items: ['Report 1', 'Report 2', 'Report 3', 'Report 4', 'Report 5']
          }
        ]
      };
    }
  };
  </script>
  
  <style scoped>
 
 <style scoped>
body {
  margin: 0; /* Remove any default margin */
  padding: 0; /* Remove any default padding */
}

.navbar {
  display: flex;
  align-items: center;
  background-color: #f4f4f4; /* Light gray for navbar background */
  padding: 10px 20px;
  position: fixed; /* Fix navbar to the top */
  top: 0; /* Ensure it's at the top of the page */
  left: 0;
  right: 0;
  z-index: 1000; /* Ensure it stays on top of other content */
}

.logo img {
  max-height: 40px;
}

.menu {
  list-style-type: none;
  display: flex;
  margin: 0;
  padding: 0;
  margin-left: auto;
}

.menu-item {
  position: relative;
  margin-right: 20px;
}

.menu-item a {
  color: #3f5864; /* Dark blue text for contrast */
  text-decoration: none;
  font-weight: bold;
  padding: 10px;
  display: block;
}

.menu-item:hover .dropdown {
  display: block;
}

.dropdown {
  display: none;
  position: absolute;
  background-color: #f38b3c; /* Orange color for the dropdown */
  list-style-type: none;
  margin: 0;
  padding: 0;
  min-width: 150px;
  top: 100%; /* Position dropdown right below the menu item */
}

.dropdown li {
  border-bottom: 1px solid #ffc700; /* Yellow border for dropdown items */
}

.dropdown li:last-child {
  border-bottom: none;
}

.dropdown a {
  color: #3f5864; /* Dark blue for dropdown items */
  text-decoration: none;
  padding: 10px;
  display: block;
}

.dropdown a:hover {
  background-color: #65c058; /* Green background on hover */
  color: #ffffff;
}

/* Add padding to the body content to avoid it hiding under the fixed navbar */
.content {
  padding-top: 60px; /* Adjust this value based on the height of your navbar */
}
</style>


