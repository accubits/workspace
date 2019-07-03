import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-org-detail-pop',
  templateUrl: './org-detail-pop.component.html',
  styleUrls: ['./org-detail-pop.component.scss']
})
export class OrgDetailPopComponent implements OnInit {

  currentTab : string = 'details';
  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }


  ngOnInit() {
  }

  closePop(){
    this.superAdminDataService.orgDetailPop.show = false;
  }
}
