* {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
  }
  
  body {
    background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url("Home.jpg"); 
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;


    line-height: 1.6;
    font-family: 'Rubik', sans-serif;
  }
  
  ul {
    list-style-type: none;
  }
  
  a {
    text-decoration: none;
    color:#333;
  }
  
  h1,
  h2 {
    font-weight: 600;
    line-height: 1.2;
    margin: 10px 0;
  }
  
  p {
    margin: 10px 0;
  }
  

  
  .navbar {
  
    background-color:transparent;
    height: 75px;
   
  }
  
  .navbar ul {
    display: flex;
  }
  
  .navbar a {
    color: #fff;
    padding: 10px;
    margin: 0 5px;
  }
  
  .navbar a:hover {
    border-bottom: 2px #fff solid;
  }
  
  .navbar .flex {
    justify-content: space-between;
  }
  
  .container {
    width: 90%;
    margin: 0 auto;
    overflow: auto;
    padding: 15px 40px;
  }
  
  .flex {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .Content {
    height: 90vh;

    background-position:left;
    background-repeat: no-repeat;
    background-size: cover;
    position: relative;
  }
  
  .text {
    text-align:left;
    position: absolute;
    top: 50%;
    left: 25%;
    transform: translate(-50%, -50%);
    color: #fff;
    width: 400px;
  }
  
 
  .weather {
    color: #fff;
    position: absolute;
    right: 200px;
    top: 130px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
  
  }

  .location {
    height: 15vh;
    display: flex;
    justify-content: space-around;

  }

  .degree-section {
    display: flex;
    align-items: center;
    cursor: pointer;
    
  }

  .degree-section span {
    margin: 10px;
    font-size: 30px;
  }

  .degree-section h2 {
    font-size: 40px;
  }

  .temperature-description {
    font-size: 25px;
    position: absolute;
    align-items: center;
    /*right: 40px;*/
  }

  