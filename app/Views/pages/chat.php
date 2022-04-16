<?= $this->extend("App\Views\home") ?>
<?= $this->section('main') ?>
<?= $this->section('javascript') ?>
<script src="/assets/js/socket.io.js?v=2.0.2"></script>
    <script type="text/javascript">
      var socket = io("https://auruby.com", {
        withCredentials: true,
        extraHeaders: {
          "username": "<?php echo user_id();?>"
        }
      });
      
      const inboxPeople = document.querySelector(".inbox__people");
      const inputField = document.querySelector(".message_form__input");
      const messageForm = document.querySelector(".message_form");
      const messageBox = document.querySelector(".messages__history");
      const fallback = document.querySelector(".fallback");

      let userName = "";

      const newUserConnected = (user) => {
        userName = user || `User${Math.floor(Math.random() * 1000000)}`;
        socket.emit("new user", <?php echo user_id();?>);
        addToUsersBox(<?php echo user_id();?>);
      };

      const addToUsersBox = (userName) => {
        if ($('.'+userName+'-userlist').length > 0) {
          return;
        }

        const userBox = `
          <div class="chat_ib ${userName}-userlist">
            <h5>${userName}</h5>
          </div>
        `;
        inboxPeople.innerHTML += userBox;
      };

      const addNewMessage = ({ user, message }) => {
        const time = new Date();
        const formattedTime = time.toLocaleString("en-US", { hour: "numeric", minute: "numeric" });

        const receivedMsg = `
        <li class="list-group-item text-right border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                  
                  
                
            <div class="incoming__message">
              <div class="d-flex flex-column received__message">
                
                <div class="message__info">
                  <span class="mb-2 text-xs message__author">${user} | ${formattedTime}</span>
                </div>
                <h6 class="mb-3 text-sm">${message}</h6>
              </div>
            </div>
        </li>
        `;

        const myMsg = `
        <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                  
                  
                
            <div class="incoming__message">
              <div class="d-flex flex-column received__message">
                
                <div class="message__info">
                  <span class="mb-2 text-xs message__author">Your | ${formattedTime}</span>
                  
                </div>
                <h6 class="mb-3 text-sm">${message}</h6>
              </div>
            </div>
        </li>`;

        if($("ul.messages__history li:first").length > 0){
          $("ul.messages__history li:first").before(user === userName ? myMsg : receivedMsg);
        }else{
          $("ul.messages__history").append(user === userName ? myMsg : receivedMsg);
        }
        
      };


      const addNewMessageReward =(data) =>{
        var html = `<tr>
                      <td>
                        <div class="d-flex align-items-center">
                          
                          <div class="d-flex flex-column">
                            <h6 class="mb-1 text-dark text-sm">${data.username} | ${data.ip}</h6>
                            <span class="text-xs">Click : ${data.start} | ${data.name} | Lead : ${data.lead}</span>
                          </div>
                        </div>
                      </td>
                      
                      <td class="text-end">${data.price} $</td>
                      
                      
                    </tr>`;
        if($("#orderComplete tbody tr").length > 0){
          $("#orderComplete tbody tr:first").before(html);
        }else{
          $("#orderComplete tbody").append(html);
        }
        if($("#orderComplete tbody tr").length > 10){
          $("#orderComplete tbody tr:last").remove();
        }
        const audio = new Audio("/assets/sound/qcodes_3.mp3" );
        audio.play();
      }
      // new user is created so we generate nickname and emit event
      newUserConnected();

      messageForm.addEventListener("submit", (e) => {
        e.preventDefault();
        if (!inputField.value) {
          return;
        }

        socket.emit("chat message", {
          message: inputField.value,
          id: <?php echo user_id();?>,
          username: "<?php echo user()->username;?>"
        });

        inputField.value = "";
      });

      inputField.addEventListener("keyup", () => {
        socket.emit("typing", {
          isTyping: inputField.value.length > 0,
          nick: "<?php echo user()->username;?>",
        });
      });

      socket.on("new user", function (data) {
        data.map((user) => addToUsersBox(user));
      });

      socket.on("user disconnected", function (userName) {
        document.querySelector(`.${userName}-userlist`).remove();
      });

      socket.on("chat message", function (data) {
        addNewMessage({ user: data.username, message: data.message });
      });

      socket.on("signal reward", function (data) {
        addNewMessageReward(data);
      });

      socket.on("typing", function (data) {
        const { isTyping, nick } = data;

        if (!isTyping) {
          fallback.innerHTML = "";
          return;
        }

        fallback.innerHTML = `<p>${nick} is typing...</p>`;
      });
</script>
<?= $this->endSection() ?>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-lg-8">
          <div class="row">
            <div class="col-xl-6 mb-xl-0 mb-4">
              <div class="card bg-transparent shadow-xl">
                <div class="overflow-hidden position-relative border-radius-xl" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/card-visa.jpg');">
                  <span class="mask bg-gradient-dark"></span>
                  <div class="card-body position-relative z-index-1 p-3">
                    <i class="fas fa-wifi text-white p-2"></i>
                    <h5 class="text-white mt-4 mb-5 pb-2">4562&nbsp;&nbsp;&nbsp;1122&nbsp;&nbsp;&nbsp;4594&nbsp;&nbsp;&nbsp;7852</h5>
                    <div class="d-flex">
                      <div class="d-flex">
                        <div class="me-4">
                          <p class="text-white text-sm opacity-8 mb-0">Card Holder</p>
                          <h6 class="text-white mb-0">Jack Peterson</h6>
                        </div>
                        <div>
                          <p class="text-white text-sm opacity-8 mb-0">Expires</p>
                          <h6 class="text-white mb-0">11/22</h6>
                        </div>
                      </div>
                      <div class="ms-auto w-20 d-flex align-items-end justify-content-end">
                        <img class="w-60 mt-2" src="../assets/img/logos/mastercard.png" alt="logo">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fas fa-landmark opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Lead</h6>
                      <span class="text-xs">Lead Number</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0"><?php echo $report->total_lead;?></h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                  <div class="card">
                    <div class="card-header mx-4 p-3 text-center">
                      <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fab fa-paypal opacity-10"></i>
                      </div>
                    </div>
                    <div class="card-body pt-0 p-3 text-center">
                      <h6 class="text-center mb-0">Total</h6>
                      <span class="text-xs">Total Work</span>
                      <hr class="horizontal dark my-3">
                      <h5 class="mb-0">$<?php echo $report->total_money;?></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <div class="row">
                <div class="col-6 d-flex align-items-center">
                  <h6 class="mb-0">Test Offer</h6>
                </div>
                <div class="col-6 text-end">
                  <button class="btn btn-outline-primary btn-sm mb-0">View All</button>
                </div>
              </div>
            </div>
            <div class="card-body p-3 pb-0">
              <ul class="list-group">
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark font-weight-bold text-sm">March, 01, 2020</h6>
                    <span class="text-xs">#MS-415646</span>
                  </div>
                  <div class="d-flex align-items-center text-sm">
                    $180
                    <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                  </div>
                </li>
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                  <div class="d-flex flex-column">
                    <h6 class="text-dark mb-1 font-weight-bold text-sm">February, 10, 2021</h6>
                    <span class="text-xs">#RV-126749</span>
                  </div>
                  <div class="d-flex align-items-center text-sm">
                    $250
                    <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-pdf text-lg me-1"></i> PDF</button>
                  </div>
                </li>
                
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-0" style="display: none;">
          <div class="inbox">
            <div class="inbox__people">
              <h4>Active users</h4>
            </div>
            
          </div>
        </div>
        <div class="col-md-6 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <h6 class="mb-0">Chat's</h6>
            </div>
            <div class="card-body pt-4 p-3">
              <div class="inbox__messages" style="height:620px; overflow-y: auto;">
                <ul class="messages__history list-group">
                  <?php foreach ($chat as $key => $value) { ?>
                   
                  <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                      <div class="incoming__message">
                        <div class="d-flex flex-column received__message">
                          
                          <div class="message__info">
                            <span class="mb-2 text-xs message__author"><?php echo $value->username;?> | <?php echo $value->created_at;?></span>
                            
                          </div>
                          <h6 class="mb-3 text-sm"><?php echo $value->messages;?></h6>
                        </div>
                      </div>
                  </li>
                  <?php } ?>
                </ul>
                <div class=""></div>
                
              </div>
              <div class="fallback"></div>
              
              <form class="message_form">
                <div class="input-group">
                  <input type="text" class="message_form__input form-control input-sm" placeholder="Enter Msg">
                  <button class="btn btn-lg btn-secondary message_form__button" type="submit" id="button-addon2">Send</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-6 mt-4">
          <div class="card h-100 mb-4">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="mb-0">Leading (All member)</h6>
                </div>
                <div class="col-md-6 d-flex justify-content-end align-items-center">
                  <i class="far fa-calendar-alt me-2"></i>
                  <small><?php echo date("Y-m-d",now());?></small>
                </div>
              </div>
            </div>
            <div class="card-body pt-4 p-3">
              <table class="table align-items-center mb-0" id="orderComplete">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leader</th>
                      
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Reward</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($finish as $item){ ?>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          
                          <div class="d-flex flex-column">
                            <h6 class="mb-1 text-dark text-sm"><b class="text-<?php echo ($item->auth_id == user_id() ? "primary" : "secondary");?>"><?php echo $item->firstname;?> <?php echo $item->lastname;?></b> | <?php echo strtoupper($item->ip);?></h6>
                            <span class="text-xs">Click : <?php echo date("d-m h:i A",$item->created_at);?> | <?php echo $item->name;?> | Lead delay: <?php echo delay_timeago($item->updated_at,$item->created_at);?></span>
                          </div>
                        </div>
                      </td>
                      
                      <td class="text-end"><?php echo $item->cost;?>$</td>
                      
                      
                    </tr>
                    <?php } ?>
                  </tbody>
              </table>
                
              
            </div>
          </div>
        </div>
      </div>
      
      <div class="card mt-3">
          <div class="card-header border-bottom">Offer Task</div>
          <div class="card-body">
            <table class="table">
              <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Device</th>
                <th>Country</th>
                <th>Link</th>
                <th>Click</th>
                <th class="text-end">Lead</th>
                
              </thead>
              <tbody>
                <?php foreach ($offer as $key => $value) { ?>
                  
                <tr>
                  <td><?php echo $value->id;?></td>
                  <td><h6 class="mb-1 text-dark text-sm"><?php echo $value->name;?></h6>
                    
                    <span class="text-xs"><?php echo $value->description;?></span>
                  </td>
                  <td><?php echo $value->cost;?></td>
                  <td><?php echo $value->device;?></td>
                  <td><?php echo $value->country;?></td>
                  <td>
                    <div class="input-group">
                      <input type="text" class="form-control" readonly value="<?php echo base_url("click-".$value->id."-".user_id().".html");?>">
                      <button class="btn btn-primary" type="button" id="button-addon2">Copy</button>
                    </div>
                  </td>
                  <td><?php echo $value->click_number;?></td>
                  <td class="text-end"><?php echo $value->lead_number;?> / <?php echo $value->maxlead;?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  
<?= $this->endSection() ?>