import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenseTableHeadComponent } from './license-table-head.component';

describe('LicenseTableHeadComponent', () => {
  let component: LicenseTableHeadComponent;
  let fixture: ComponentFixture<LicenseTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenseTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenseTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
