import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';

@Component({
  selector: 'app-footer-option',
  templateUrl: './footer-option.component.html',
  styleUrls: ['./footer-option.component.scss']
})
export class FooterOptionComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

  deleteForms():void{
    this.dataService.confirmPop.show  = true;
  }

}
