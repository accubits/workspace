import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PartnerTableComponent } from './partner-table.component';

describe('PartnerTableComponent', () => {
  let component: PartnerTableComponent;
  let fixture: ComponentFixture<PartnerTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PartnerTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PartnerTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
