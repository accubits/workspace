import { TestBed, inject } from '@angular/core/testing';

import { HrmUtilityService } from './hrm-utility.service';

describe('HrmUtilityService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [HrmUtilityService]
    });
  });

  it('should be created', inject([HrmUtilityService], (service: HrmUtilityService) => {
    expect(service).toBeTruthy();
  }));
});
