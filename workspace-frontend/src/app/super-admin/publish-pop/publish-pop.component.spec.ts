import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PublishPopComponent } from './publish-pop.component';

describe('PublishPopComponent', () => {
  let component: PublishPopComponent;
  let fixture: ComponentFixture<PublishPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PublishPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PublishPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
