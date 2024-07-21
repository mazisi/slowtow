<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head>
<body>
<h2 class="text-center">{{ $company->name }}</h2>
<table id="customers">
  <!-- <tr>
    <th>Company</th>
    <th>Contact</th>
  </tr> -->
  <tr>
    <td>Company Name</td>
    <td>{{ $company->name }}</td>
  </tr>
  <tr>
    <td>Company Type</td>
    <td>{{ $company->company_type }}</td>
  </tr>
  <tr>
    <td>Registration Number</td>
    <td>{{ $company->reg_number }}</td>
  </tr>
  <tr>
    <td>Vat Number</td>
    <td>{{ $company->vat_number }}</td>
  </tr>
  <tr>
    <td>Website</td>
    <td>{{ $company->website }}</td>
  </tr>
  <tr>
    <td>Email Address</td>
    <td>{{ $company->email }}</td>
  </tr>
  <tr>
    <td>Email Address</td>
    <td>{{ $company->email1 }}</td>
  </tr>
  <tr>
    <td>Email Address</td>
    <td>{{ $company->email2 }}</td>
  </tr>
  <tr>
    <td>Phone Number</td>
    <td>{{ $company->tel_number }}</td>
  </tr>
  <tr>
    <td>Phone Number</td>
    <td>{{ $company->tel_number1 }}</td>
  </tr>
  <tr>
    <td>Business Address Line 1</td>
    <td>{{ $company->address_line }}</td>
  </tr>
  <tr>
    <td>Business Address Line 2</td>
    <td>{{ $company->address_line2 }}</td>
  </tr>
  <tr>
    <td>Business Address Line 3</td>
    <td>{{ $company->address_line3 }}</td>
  </tr>
  <tr>
    <td>Province</td>
    <td>{{ $company->business_province }}</td>
  </tr>
  <tr>
    <td>Postal Code</td>
    <td>{{ $company->business_address_postal_code }}</td>
  </tr>
  <tr>
    <td>Postal Address Line 1</td>
    <td>{{ $company->postal_address }}</td>
  </tr>
  <tr>
    <td>Postal Address Line 2</td>
    <td>{{ $company->postal_address2 }}</td>
  </tr>
  <tr>
    <td>Postal Address Line 3</td>
    <td>{{ $company->postal_address3 }}</td>
  </tr>
  <tr>
    <td>Province</td>
    <td>{{ $company->postal_province }}</td>
  </tr>
  <tr>
    <td>Postal Code</td>
    <td>{{ $company->postal_code }}</td>
  </tr>
</table>

</body>
</html>


