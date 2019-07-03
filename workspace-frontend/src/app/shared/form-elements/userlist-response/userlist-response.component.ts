import { Component, OnInit } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-userlist-response',
  templateUrl: './userlist-response.component.html',
  styleUrls: ['./userlist-response.component.scss']
})
export class UserlistResponseComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
