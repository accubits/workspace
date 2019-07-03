import { Component, OnInit } from '@angular/core';
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerDataService} from '../../shared/services/partner-data.service'


@Component({
  selector: 'app-license',
  templateUrl: './license.component.html',
  styleUrls: ['./license.component.scss']
})
export class LicenseComponent implements OnInit {
  

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
  }

  
}
