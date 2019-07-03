import { TestBed, inject } from '@angular/core/testing';

import { CrmDataService } from './crm-data.service';

describe('CrmDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CrmDataService]
    });
  });

  it('should be created', inject([CrmDataService], (service: CrmDataService) => {
    expect(service).toBeTruthy();
  }));
});
