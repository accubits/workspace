import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';
 
@Component({
  selector: 'app-request-table',
  templateUrl: './request-table.component.html',
  styleUrls: ['./request-table.component.scss']
})
export class RequestTableComponent implements OnInit {

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  showRequest(){
    this.superAdminDataService.requestDetail.show = true;
  }

  check(event){
    event.stopPropagation();
  }

  
}
