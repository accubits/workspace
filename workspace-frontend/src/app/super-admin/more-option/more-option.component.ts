import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service'

@Component({
  selector: 'app-more-option',
  templateUrl: './more-option.component.html',
  styleUrls: ['./more-option.component.scss']
})
export class MoreOptionComponent implements OnInit {

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }
  
  closepop(){
    this.superAdminDataService.more.show = false;
  }
}
