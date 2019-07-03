import { Component, OnInit } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-userlist-submit',
  templateUrl: './userlist-submit.component.html',
  styleUrls: ['./userlist-submit.component.scss']
})
export class UserlistSubmitComponent implements OnInit {
  activeRpTab = 'recent';
  userlistingdrop:boolean =  false;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
