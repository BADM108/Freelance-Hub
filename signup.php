<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <style>
        body {
            background-color: #f9fafb;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 110vh;
        }
        
        .container {
            position: relative;
            /* needed for close button positioning */
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            height: 92vh;
            align-items: center;
        }
        /* Close Button */
        
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 22px;
            color: #888;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        
        .close-btn:hover {
            color: #4f46e5;
            transform: scale(1.2);
        }
        
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 24px;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        
        label {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            margin-right: 10px;
        }
        
        select {
            padding: 10px;
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        select:hover {
            border-color: #888;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }
        
        select:focus {
            border-color: #4a90e2;
            box-shadow: 0 4px 10px rgba(74, 144, 226, 0.3);
            transform: scale(1.02);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #4f46e5;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        
        button:hover {
            background-color: #4338ca;
            transform: scale(1.02);
        }
        
        .message {
            margin-top: 1rem;
            text-align: center;
            font-size: 14px;
            color: red;
        }
        
        .signin-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 14px;
            color: #4f46e5;
            text-decoration: none;
        }
        
        .form-group {
            gap: 1px;
            padding: 15px;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
    
       <form action="signupS.php" method="POST" >

        <a href="freelanceHubHome.php" class="close-btn">&times;</a>

        <h2>Sign Up</h2>
        <div class="form-group">

            <input type="text" id="fname" name="fname" placeholder="First name" oninput="numericalinnames()" required />
            <input type="text" id="lname" name="lname" placeholder="Last name" oninput="numericalinnames()" required />
            <input type="email" id="email" name="email" placeholder="Email" required />
            <input type="password" id="password" name="password" placeholder="Password" required />
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" oninput="validateConfirmPassword()" required />
            <input type="number" id="age" name="age" placeholder="Age" oninput="validateAge()" required />
            <script>
                function numericalinnames()
                {
                    const fname = document.getElementById('fname').value;
                    const lname = document.getElementById('lname').value;
                    const nameRegex = /^[A-Za-z]+$/;
                    const fnameInput = document.getElementById('fname');
                    const lnameInput = document.getElementById('lname');
                    if (!nameRegex.test(fname)) {
                        fnameInput.setCustomValidity("First name must contain only letters.");
                        fnameInput.style.borderColor = 'red';
                    } else {
                        fnameInput.setCustomValidity("");
                        fnameInput.style.borderColor = '#21ae21ff';
                    }
                    if (!nameRegex.test(lname)) {
                        lnameInput.setCustomValidity("Last name must contain only letters.");
                        lnameInput.style.borderColor = 'red';
                    } else {
                        lnameInput.setCustomValidity("");
                        lnameInput.style.borderColor = '#21ae21ff';
                    }
                }
                function validateAge() {
                    const ageInput = document.getElementById('age');
                    const age = parseInt(ageInput.value, 10);
                    if (isNaN(age) || age < 18) {
                        ageInput.setCustomValidity("You must be at least 18 years old.");
                        ageInput.style.borderColor = 'red';
                    } else {
                        ageInput.setCustomValidity("");
                        ageInput.style.borderColor = '#21ae21ff';
                    }
                }
                function validateConfirmPassword() {
                    const password = document.getElementById('password');
                    const confirm = document.getElementById('confirm-password');
                    if (password.value.length < 8) {
                        password.setCustomValidity("Password must be at least 8 characters long.");
                        password.style.borderColor = 'red';
                    } else if (/[^A-Za-z0-9]/.test(password.value)) {
                        password.setCustomValidity("Password must not contain special characters.");
                        password.style.borderColor = 'red';
                    } else {
                        password.setCustomValidity("");
                        password.style.borderColor = '#18b351ff';
                    }
                    if (confirm.value !== password.value) {
                        confirm.setCustomValidity("Passwords do not match");
                        confirm.style.borderColor = 'red';
                    } else {
                        confirm.setCustomValidity("");
                        confirm.style.borderColor = '#13a43aff';
                    }
                }
                  
                
                function validateForm() {
                    validateConfirmPassword();
                    validateAge();
                }
                
            </script>
            


            <label for="country">Choose a country</label>
            <select id="country" name="country" required>
             <option value="Afghanistan">Afghanistan</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Brazil">Brazil</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cabo Verde">Cabo Verde</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
            <option value="Congo, Republic of the">Congo, Republic of the</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Eswatini">Eswatini</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Greece">Greece</option>
            <option value="Grenada">Grenada</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Honduras">Honduras</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="North Korea">North Korea</option>
            <option value="North Macedonia">North Macedonia</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Palestine">Palestine</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Qatar">Qatar</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Korea">South Korea</option>
            <option value="South Sudan">South Sudan</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Timor-Leste">Timor-Leste</option>
            <option value="Togo">Togo</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican City">Vatican City</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
            </select><br><br>
           
           <label for="role">Choose a role:</label>
           <select id="role" name="role" required>
           <option value="freelancer">Freelancer</option>
           <option value="poster">Job Poster</option>
           </select>
            
            <button type="submit" id="submit">Sign Up</button>
            
            <div>
                <p class="message" id="message"></p>
            </div>
            <div>
                <a href="signin.php" class="signin-link">Already have an account? Click here to Sign In</a>
            </div>
          </div>  

          </form> 
          </div>
    </body> 


</html>