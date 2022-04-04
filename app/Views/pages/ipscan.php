<?= $this->extend("App\Views\home") ?>
<?= $this->section('main') ?>
    <?= $this->section('javascript') ?>

      <script type="text/javascript">
        $(".check").on("click", function(){
            var ip = $(this).attr("data-ip");
            var country = $(this).parent().parent().find(".country");
            var state = $(this).parent().parent().find(".state");
            var zip = $(this).parent().parent().find(".zip");
            axios.get("http://ip-api.com/json/"+ip).then((item) => {
                var data = item.data;
                country.text(data.countryCode);
                state.text(data.region);
                zip.text(data.zip);
                //$("#tablesignal tbody tr").eq(i).find("td").eq(3).text(item.country);
            });
        });
        
      </script>
    <?= $this->endSection() ?>


    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-xl-6 mb-xl-0 mb-4">
              <div class="card bg-transparent shadow-xl">
                <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('/assets/img/ivancik.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 p-4">
                    <i class="fas fa-wifi text-white p-2"></i> Server connect
                    <h5 class="text-white mt-2 mb-2 pb-2">Smart AI. Auto Scan IP</h5>
                    <p>Time GMT +2 | <?php echo date('m-d-Y H:i A');?></p>
                    <div class="d-flex">
                      <div class="d-flex">
                        <div class="me-4">
                          <p class="text-white text-sm opacity-8 mb-0">ECN Broker</p>
                          <h6 class="text-white mb-0">Open Account</h6>
                        </div>
                        <div>
                          <p class="text-white text-sm opacity-8 mb-0">Pro Broker</p>
                          <h6 class="text-white mb-0">Open Account</h6>
                        </div>
                      </div>
                      <div class="ms-auto w-20 d-flex align-items-end justify-content-end">
                        <img class="w-40 mt-2 border-radius-lg bg-white p-3" src="/assets/img/logo-ct-dark.png" alt="logo">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="row">
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fas fa-landmark opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Signal</h6>
                      <span class="text-xs">Total Number Order</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0 totalsignal">0</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mt-md-0 mt-4">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fab fa-paypal opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">IP</h6>
                      <span class="text-xs">Total Pips Win</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0 pipswin">0</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mt-md-0 mt-4">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fab fa-paypal opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Country</h6>
                      <span class="text-xs">Total Pips SL</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0 piploss">0</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mt-md-0 mt-4">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fab fa-paypal opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Update</h6>
                      <span class="text-xs">Total USD</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0 usdwin">0</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        
      </div>
      <div class="row">
        <div class="col-lg-7 mt-4">
          
          
          
            <div class="card mb-3">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="col-6 d-flex align-items-center">
                  <h6 class="mb-0">Public IP</h6>
                </div>
                <div class="col-6 text-end">
                  
                  <?php echo components("updateaccount",['text' => '<i class="fas fa-plus"></i>&nbsp;&nbsp;Update IP', 'class' => 'btn bg-gradient-dark mb-0']);?>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="tablesignal">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IP</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Port</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Country</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">State</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Zip</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($ip as $key => $value) { ?>
                      <tr>
                        <td class="tdip"><?php echo $value->ip;?></td>
                        <td><?php echo $value->port;?></td>
                        <td class="country"></td>
                        <td class="state"></td>
                        <td class="zip"></td>
                       
                        <td><?php echo $value->type;?></td>
                        <td><button class="btn btn-sm btn-info check" data-ip="<?php echo $value->ip;?>">Check</button></td>
                      </tr>
                    <?php }  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

         

        </div>
        <div class="col-lg-5 mt-4">
          
          <div class="card mb-4">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="mb-0">Last Connect</h6>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                  <i class="far fa-calendar-alt me-2"></i>
                  <small><?php echo date('d-m h:i A');?></small>
                </div>
              </div>
            </div>
            <div class="card-body pt-4 p-3">
              
              
              
              
            </div>
          </div>
        </div>
      </div>
     
    </div>

<?= $this->endSection() ?>