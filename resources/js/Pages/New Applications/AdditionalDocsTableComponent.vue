<template>
  <div class="container mt-5 text-left">
    <form>
        <div class="row justify-content-center">
            <div class="col-md-3 mb-3">
                <label for="labelDescription" class="form-label">Label Description</label>
                <textarea class="form-control" id="labelDescription" rows="3"></textarea>
            </div>

            <div class="col-md-3 mb-3">
                <label for="referenceLabel" class="form-label">Reference Label</label>
                <input type="text" class="form-control border" id="referenceLabel">
            </div>

            <div class="col-md-3 mb-3">
                <label for="fileInput" class="form-label">File Upload</label>
                <input type="file" class="form-control border" id="fileInput">
            </div>

            <div class="col-md-3 mb-3">
              <br/>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </form>
</div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Document Description</th>
        <th scope="col">Upload Date</th>
        <th scope="col">View Document</th>
        <th scope="col">Edit</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>Mark</td>
        <td>Otto</td>
        <td>@mdo</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Jacob</td>
        <td>Thornton</td>
        <td>@fat</td>
      </tr>
     
    </tbody>
  </table>
   
</template>