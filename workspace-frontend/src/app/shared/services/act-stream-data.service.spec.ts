import { TestBed, inject } from '@angular/core/testing';

import { ActStreamDataService } from './act-stream-data.service';

describe('ActStreamDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ActStreamDataService]
    });
  });

  it('should be created', inject([ActStreamDataService], (service: ActStreamDataService) => {
    expect(service).toBeTruthy();
  }));
});
