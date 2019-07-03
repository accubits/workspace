import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PartnerHeadComponent } from './partner-head.component';

describe('PartnerHeadComponent', () => {
  let component: PartnerHeadComponent;
  let fixture: ComponentFixture<PartnerHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PartnerHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PartnerHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
