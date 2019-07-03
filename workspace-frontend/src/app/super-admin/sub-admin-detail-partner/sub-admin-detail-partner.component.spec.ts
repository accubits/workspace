import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubAdminDetailPartnerComponent } from './sub-admin-detail-partner.component';

describe('SubAdminDetailPartnerComponent', () => {
  let component: SubAdminDetailPartnerComponent;
  let fixture: ComponentFixture<SubAdminDetailPartnerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubAdminDetailPartnerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubAdminDetailPartnerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
