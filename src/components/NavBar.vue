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
            <a :href="item.path">{{ item.title }}</a>
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
          items: [
            { title: 'Series Editor', path: '/email/series/any/1' },
            { title: 'Send Que', path: '/email/que' },
            { title: 'Send Direct', path: '/email/direct/' },
            { title: 'Email 4', path: '/emails/4' },
            { title: 'Email 5', path: '/emails/5' }
          ]
        },
        {
          name: 'Materials',
          items: [
            { title: 'Material 1', path: '/materials/1' },
            { title: 'Material 2', path: '/materials/2' },
            { title: 'Material 3', path: '/materials/3' },
            { title: 'Material 4', path: '/materials/4' },
            { title: 'Material 5', path: '/materials/5' }
          ]
        },
        {
          name: 'People',
          items: [
            { title: 'Person 1', path: '/people/1' },
            { title: 'Person 2', path: '/people/2' },
            { title: 'Person 3', path: '/people/3' },
            { title: 'Person 4', path: '/people/4' },
            { title: 'Person 5', path: '/people/5' }
          ]
        },
        {
          name: 'Reports',
          items: [
            { title: 'Report 1', path: '/reports/1' },
            { title: 'Report 2', path: '/reports/2' },
            { title: 'Report 3', path: '/reports/3' },
            { title: 'Report 4', path: '/reports/4' },
            { title: 'Report 5', path: '/reports/5' }
          ]
        }
      ]
    };
  }
};


  </script>
  

 
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


<style scoped>
body {
  margin: 0;
  padding: 0;
}

.navbar {
  display: flex;
  align-items: center;
  background-color: #f4f4f4;
  padding: 10px 20px;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
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
  color: #3f5864;
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
  background-color: #f38b3c;
  list-style-type: none;
  margin: 0;
  padding: 0;
  min-width: 180px;
  top: 100%;
  opacity: 0;
  transition: opacity 0.3s ease, transform 0.3s ease;
  transform: translateY(10px);
}

.menu-item:hover .dropdown {
  opacity: 1;
  transform: translateY(0);
}

.dropdown li {
  border-bottom: 1px solid #ffc700;
}

.dropdown li:last-child {
  border-bottom: none;
}

.dropdown a {
  color: #3f5864;
  text-decoration: none;
  padding: 10px;
  display: block;
}

.dropdown a:hover {
  background-color: #65c058;
  color: #ffffff;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .menu {
    display: none;
    flex-direction: column;
    width: 100%;
  }

  .menu-item {
    width: 100%;
  }

  .menu-item a {
    padding: 15px;
    text-align: center;
  }

  .navbar {
    justify-content: space-between;
  }

  .navbar .menu {
    flex-direction: column;
  }

  .menu-toggle {
    display: block;
    cursor: pointer;
  }
}
</style>