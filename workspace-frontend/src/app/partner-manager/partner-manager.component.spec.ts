import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PartnerManagerComponent } from './partner-manager.component';

describe('PartnerManagerComponent', () => {
  let component: PartnerManagerComponent;
  let fixture: ComponentFixture<PartnerManagerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PartnerManagerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PartnerManagerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
