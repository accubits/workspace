import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseDetailsPopComponent } from './license-details-pop.component';

describe('LicenseDetailsPopComponent', () => {
  let component: LicenseDetailsPopComponent;
  let fixture: ComponentFixture<LicenseDetailsPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseDetailsPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseDetailsPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
