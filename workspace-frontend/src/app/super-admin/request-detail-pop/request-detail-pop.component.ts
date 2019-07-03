import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-request-detail-pop',
  templateUrl: './request-detail-pop.component.html',
  styleUrls: ['./request-detail-pop.component.scss']
})
export class RequestDetailPopComponent implements OnInit {
  public currentTab : string = 'current' ;

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closeReqDetails(){
    this.superAdminDataService.requestDetail.show = false;
  }
}
